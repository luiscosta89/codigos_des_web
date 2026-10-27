<?php
declare(strict_types=1);

$falhas=[]; $testes=0;
function testar(string $nome, callable $fn): void { global $falhas,$testes; $testes++; try{$fn();echo "OK  $nome\n";}catch(Throwable $e){$falhas[]="$nome: {$e->getMessage()}";echo "FALHA $nome: {$e->getMessage()}\n";} }
function afirmar(bool $v,string $m='asserção falhou'):void{if(!$v)throw new RuntimeException($m);}
function igual(mixed $e,mixed $o,string $m=''):void{afirmar($e===$o,$m?:'esperado '.var_export($e,true).', obtido '.var_export($o,true));}
require __DIR__.'/../publicar/includes/conexao.php';
require __DIR__.'/../publicar/includes/repositorio_eventos.php';
require __DIR__.'/../publicar/includes/repositorio_usuarios.php';
$config=['host'=>getenv('DB_HOST')?:'db','usuario'=>'aula13','senha'=>'teste-aula13','banco'=>'mural_aula13','porta'=>3306];
$db=conectar($config);

testar('instalação limpa cria 2 usuários e 4 eventos',function()use($db):void{
 igual(2,(int)$db->query('SELECT COUNT(*) n FROM usuarios')->fetch_assoc()['n']);
 igual(4,(int)$db->query('SELECT COUNT(*) n FROM eventos')->fetch_assoc()['n']);
});

testar('senhas seed autenticam ana e bruno',function()use($db):void{
 igual('ana',autenticar_usuario($db,'ana','aula13')['login']??null);
 igual('bruno',autenticar_usuario($db,'bruno','aula13')['login']??null);
});

testar('listagem e busca textual ficam no proprietário',function()use($db):void{
 $ana=listar_eventos($db,1,''); $bruno=listar_eventos($db,2,'');
 igual(2,count($ana)); igual(2,count($bruno));
 afirmar(count(listar_eventos($db,1,'Semana'))===1);
 afirmar(count(listar_eventos($db,2,'Semana'))===0);
 foreach($ana as $e) igual(1,(int)$e['usuario_id']);
});

testar('busca de evento alheio não revela existência',function()use($db):void{
 $idBruno=(int)$db->query('SELECT id FROM eventos WHERE usuario_id=2 LIMIT 1')->fetch_assoc()['id'];
 igual(null,buscar_evento($db,$idBruno,1));
 afirmar(buscar_evento($db,$idBruno,2)!==null);
});

testar('update e delete exigem proprietário na própria query',function()use($db):void{
 $idBruno=(int)$db->query('SELECT id FROM eventos WHERE usuario_id=2 LIMIT 1')->fetch_assoc()['id'];
 $antes=buscar_evento($db,$idBruno,2);
 $dados=['titulo'=>'INVASAO','descricao'=>'x','data_evento'=>'2026-12-12','local_evento'=>'x','situacao'=>'Publicado','cartaz'=>null,'cartaz_mime'=>null];
 igual(false,atualizar_evento($db,$idBruno,1,$dados));
 igual($antes['titulo'],buscar_evento($db,$idBruno,2)['titulo']);
 igual(false,excluir_evento($db,$idBruno,1));
 afirmar(buscar_evento($db,$idBruno,2)!==null);
});

testar('criação associa proprietário explicitamente',function()use($db):void{
 $dados=['titulo'=>'Temporário','descricao'=>'Teste','data_evento'=>'2027-01-01','local_evento'=>'Lab','situacao'=>'Rascunho','cartaz'=>null,'cartaz_mime'=>null];
 $id=criar_evento($db,1,$dados); afirmar($id>0); igual(1,(int)buscar_evento($db,$id,1)['usuario_id']); igual(true,excluir_evento($db,$id,1));
});

echo "\n$testes testes, ".count($falhas)." falhas.\n"; if($falhas)exit(1);
