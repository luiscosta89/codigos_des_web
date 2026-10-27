#!/usr/bin/env python3
import os, re, sys, urllib.request, urllib.parse, urllib.error, http.cookiejar, uuid

BASE=os.environ.get('BASE_URL','http://127.0.0.1:18083')
fail=[]; count=0; session_state={}
jar=http.cookiejar.CookieJar(); opener=urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar), urllib.request.HTTPRedirectHandler())

def req(path, method='GET', data=None, headers=None, follow=True):
    global opener
    headers=headers or {}
    if isinstance(data,dict): data=urllib.parse.urlencode(data).encode(); headers.setdefault('Content-Type','application/x-www-form-urlencoded')
    request=urllib.request.Request(BASE+path,data=data,headers=headers,method=method)
    target=opener if follow else urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar), NoRedirect())
    try:
        r=target.open(request,timeout=10); return r.status,dict(r.headers),r.read()
    except urllib.error.HTTPError as e: return e.code,dict(e.headers),e.read()
class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self,*args,**kwargs): return None

def test(name,fn):
    global count; count+=1
    try: fn(); print('OK ',name)
    except Exception as e: fail.append(f'{name}: {e}'); print('FALHA',name,':',e)
def eq(a,b):
    if a!=b: raise AssertionError(f'esperado {a!r}, obtido {b!r}')
def csrf(body):
    m=re.search(rb'name="csrf_token" value="([a-f0-9]{64})"',body)
    if not m: raise AssertionError('token CSRF ausente')
    return m.group(1).decode()
def multipart(fields,filename,content,mime):
    boundary='----aula13'+uuid.uuid4().hex; chunks=[]
    for k,v in fields.items(): chunks += [f'--{boundary}\r\nContent-Disposition: form-data; name="{k}"\r\n\r\n{v}\r\n'.encode()]
    chunks += [f'--{boundary}\r\nContent-Disposition: form-data; name="cartaz"; filename="{filename}"\r\nContent-Type: {mime}\r\n\r\n'.encode(),content,f'\r\n--{boundary}--\r\n'.encode()]
    return b''.join(chunks),{'Content-Type':f'multipart/form-data; boundary={boundary}'}

def public_security():
    status,h,b=req('/login.php'); eq(200,status)
    for k in ['Content-Security-Policy','X-Content-Type-Options','Referrer-Policy','X-Frame-Options','Cache-Control']:
        if k not in h: raise AssertionError(f'cabeçalho ausente: {k}')
    cookie=h.get('Set-Cookie','')
    for trecho in ['HttpOnly','SameSite=Lax','Path=/']:
        if trecho.lower() not in cookie.lower(): raise AssertionError(f'atributo de cookie ausente: {trecho}')
    if 'Secure' in cookie: raise AssertionError('cookie HTTP não deveria ter Secure fora de HTTPS')
    session_state['antes_login']=next((c.value for c in jar if c.name=='PHPSESSID'),None)
    if b'admin' in b or b'aula12' in b: raise AssertionError('credencial antiga exposta')
    csrf(b)
test('login tem CSRF, headers e não exibe credencial',public_security)

def login_csrf():
    status,_,_=req('/login.php',method='POST',data={'login':'ana','senha':'aula13'}); eq(403,status)
    _,_,b=req('/login.php'); token=csrf(b)
    status,_,b=req('/login.php',method='POST',data={'login':'ana','senha':'aula13','csrf_token':token}); eq(200,status)
    depois=next((c.value for c in jar if c.name=='PHPSESSID'),None)
    if not depois or depois==session_state.get('antes_login'): raise AssertionError('ID de sessão não foi regenerado no login')
    if b'Painel de eventos' not in b: raise AssertionError('login não chegou ao painel')
test('login recusa CSRF ausente e autentica Ana',login_csrf)

def isolation():
    status,_,b=req('/painel.php'); eq(200,status)
    if b'Semana Acad' not in b or b'Mostra de Projetos' in b: raise AssertionError('lista não isolada')
    eq(404,req('/eventos/formulario.php?id=3')[0])
    eq(404,req('/cartaz.php?id=3')[0])
    eq(404,req('/cartaz.php?id=../1')[0])
test('lista, formulário, cartaz e traversal respeitam proprietário/ID',isolation)

def csrf_mutations():
    eq(403,req('/eventos/salvar.php',method='POST',data={'titulo':'x'})[0])
    eq(403,req('/eventos/excluir.php',method='POST',data={'id':'1'})[0])
    eq(405,req('/logout.php')[0])
    eq(403,req('/logout.php',method='POST',data={})[0])
test('mutações e logout exigem método/CSRF',csrf_mutations)

created={'id':None}
def crud_upload():
    _,_,form=req('/eventos/formulario.php'); token=csrf(form)
    png=__import__('base64').b64decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')
    fields={'csrf_token':token,'id':'','titulo':'<script>alert(1)</script> Evento','descricao':'Descrição teste','data_evento':'2027-02-03','local_evento':'Lab','situacao':'Rascunho'}
    body,headers=multipart(fields,'cartaz.png',png,'image/png')
    status,_,panel=req('/eventos/salvar.php',method='POST',data=body,headers=headers); eq(200,status)
    if b'&lt;script&gt;alert(1)&lt;/script&gt;' not in panel or b'<script>alert(1)</script>' in panel: raise AssertionError('XSS não escapado')
    m=re.search(rb'<h2>&lt;script&gt;alert\(1\)&lt;/script&gt; Evento</h2>.*?formulario\.php\?id=(\d+)[^>]*>Editar',panel,re.S)
    if not m: raise AssertionError('id criado não localizado')
    created['id']=m.group(1).decode()
    status,h,content=req('/cartaz.php?id='+created['id']); eq(200,status); eq('image/png',h.get('Content-Type')); eq('nosniff',h.get('X-Content-Type-Options')); eq(png,content)
    arquivos_antes=set(__import__('pathlib').Path('publicar/uploads').glob('*.bin')); eq(1,len(arquivos_antes))
    _,_,edit=req('/eventos/formulario.php?id='+created['id']); token_editar=csrf(edit)
    pdf=b'%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF\n'
    alterados={'csrf_token':token_editar,'id':created['id'],'titulo':'Evento atualizado','descricao':'Descrição atualizada','data_evento':'2027-02-04','local_evento':'Auditório','situacao':'Publicado'}
    body_pdf,headers_pdf=multipart(alterados,'cartaz.pdf',pdf,'application/pdf')
    status,_,panel=req('/eventos/salvar.php',method='POST',data=body_pdf,headers=headers_pdf); eq(200,status)
    if b'Evento atualizado' not in panel: raise AssertionError('update próprio não persistiu')
    arquivos_depois=set(__import__('pathlib').Path('publicar/uploads').glob('*.bin')); eq(1,len(arquivos_depois))
    if arquivos_antes == arquivos_depois: raise AssertionError('cartaz antigo não foi substituído')
    status,h,content=req('/cartaz.php?id='+created['id']); eq(200,status); eq('application/pdf',h.get('Content-Type'))
    if 'attachment' not in h.get('Content-Disposition',''): raise AssertionError('PDF não força download')
    eq(pdf,content)
    # IDOR update/delete com id de Bruno continuam 404
    fields['id']='3'; fields['csrf_token']=token_editar; body2,headers2=multipart(fields,'cartaz.png',png,'image/png')
    eq(404,req('/eventos/salvar.php',method='POST',data=body2,headers=headers2)[0])
    eq(404,req('/eventos/excluir.php',method='POST',data={'id':'3','csrf_token':token})[0])
test('CRUD cria upload privado, escapa XSS e bloqueia IDOR',crud_upload)

def invalid_upload_cleanup():
    _,_,form=req('/eventos/formulario.php'); token=csrf(form)
    fields={'csrf_token':token,'id':'','titulo':'Inválido','descricao':'Teste','data_evento':'2027-02-03','local_evento':'Lab','situacao':'Rascunho'}
    body,headers=multipart(fields,'falso.png',b'nao-e-imagem','image/png')
    status,_,page=req('/eventos/salvar.php',method='POST',data=body,headers=headers); eq(200,status)
    if b'selecione o arquivo novamente' not in page: raise AssertionError('aviso de reseleção ausente')
test('upload inválido retorna aviso de reseleção',invalid_upload_cleanup)

def delete_logout():
    _,_,panel=req('/painel.php'); token=csrf(panel); eid=created['id']
    status,_,after=req('/eventos/excluir.php',method='POST',data={'id':eid,'csrf_token':token}); eq(200,status)
    eq(404,req('/cartaz.php?id='+eid)[0])
    _,_,panel=req('/painel.php'); token=csrf(panel)
    status,h,_=req('/logout.php',method='POST',data={'csrf_token':token},follow=False); eq(302,status)
    cookie=h.get('Set-Cookie','')
    for trecho in ['PHPSESSID=','expires=','HttpOnly','SameSite=Lax','Path=/']:
        if trecho.lower() not in cookie.lower(): raise AssertionError(f'cookie de logout sem {trecho}')
    status,_,login=req('/login.php'); eq(200,status)
    if b'Entrar no sistema' not in login: raise AssertionError('logout não retornou login')
    status,_,_=req('/painel.php',follow=False); eq(302,status)
test('exclusão remove cartaz e logout encerra sessão',delete_logout)

print(f'\n{count} testes, {len(fail)} falhas.')
if fail:
    print('\n'.join(fail)); sys.exit(1)
