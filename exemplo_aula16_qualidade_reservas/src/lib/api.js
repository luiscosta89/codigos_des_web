const endpoint = () => new URL('api/index.php', document.baseURI)

async function request(options) {
  const response = await fetch(endpoint(), options)
  const isJson = response.headers.get('content-type')?.includes('application/json')
  if (!isJson) throw new Error(`Falha HTTP ${response.status || 0}`)
  const data = await response.json()
  if (!response.ok) throw new Error(data.error?.message || `Falha HTTP ${response.status}`)
  return data
}

export function loadCatalog() {
  return request({ method: 'GET', headers: { Accept: 'application/json' } })
}

export function createReservation(payload, apiKey) {
  return request({
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-API-Key': apiKey },
    body: JSON.stringify(payload)
  })
}
