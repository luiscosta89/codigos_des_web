import { afterEach, describe, expect, it, vi } from 'vitest'
import { createReservation, loadCatalog } from './api.js'

afterEach(() => vi.unstubAllGlobals())

describe('API client', () => {
  it('loads public data from a URL relative to document.baseURI', async () => {
    const fetchMock = vi.fn().mockResolvedValue({ ok: true, headers: { get: () => 'application/json' }, json: async () => ({ equipamentos: [], reservas: [] }) })
    vi.stubGlobal('fetch', fetchMock)
    await loadCatalog()
    expect(fetchMock.mock.calls[0][0].toString()).toBe(new URL('api/index.php', document.baseURI).toString())
    expect(fetchMock.mock.calls[0][1].method).toBe('GET')
  })

  it('sends the API key only on protected POST', async () => {
    const fetchMock = vi.fn().mockResolvedValue({ ok: true, headers: { get: () => 'application/json' }, json: async () => ({ reserva: { id: 3 } }) })
    vi.stubGlobal('fetch', fetchMock)
    await createReservation({ equipamento_id: 1 }, 'chave-local')
    expect(fetchMock.mock.calls[0][1].headers['X-API-Key']).toBe('chave-local')
  })

  it('returns a safe status error when the host responds with HTML', async () => {
    vi.stubGlobal('fetch', vi.fn().mockResolvedValue({ ok: false, status: 500, headers: { get: () => 'text/html' } }))
    await expect(loadCatalog()).rejects.toThrow('Falha HTTP 500')
  })
})
