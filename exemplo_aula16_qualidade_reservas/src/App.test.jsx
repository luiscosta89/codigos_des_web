import { cleanup, render, screen } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { afterEach, describe, expect, it, vi } from 'vitest'
import App from './App.jsx'
import { createReservation, loadCatalog } from './lib/api.js'

vi.mock('./lib/api.js', () => ({
  loadCatalog: vi.fn().mockResolvedValue({
    equipamentos: [{ id: 1, nome: 'Projetor A', patrimonio: 'PAT-001', disponivel: true }],
    reservas: [{ id: 8, equipamento_id: 1, equipamento_nome: 'Projetor A', solicitante: 'Lia', inicio: '2026-11-20 09:00:00', fim: '2026-11-20 10:00:00', status: 'confirmada' }]
  }),
  createReservation: vi.fn().mockResolvedValue({ reserva: { id: 9 } })
}))

afterEach(() => { cleanup(); vi.clearAllMocks() })

describe('App', () => {
  it('lists equipment and reservations loaded from the API', async () => {
    render(<App />)
    expect(await screen.findByRole('heading', { name: 'Projetor A' })).toBeInTheDocument()
    expect(screen.getByText(/Lia/)).toBeInTheDocument()
  })

  it('submits a reservation after filling the required fields', async () => {
    const user = userEvent.setup()
    render(<App />)
    await screen.findByRole('heading', { name: 'Projetor A' })
    await user.selectOptions(screen.getByLabelText('Equipamento'), '1')
    await user.type(screen.getByLabelText('Solicitante'), 'Bia')
    await user.type(screen.getByLabelText('Início'), '2026-11-20T12:00')
    await user.type(screen.getByLabelText('Fim'), '2026-11-20T13:00')
    await user.type(screen.getByLabelText('Chave da API'), 'teste')
    await user.click(screen.getByRole('button', { name: 'Criar reserva' }))
    expect(await screen.findByRole('status')).toHaveTextContent('Reserva criada')
    expect(createReservation).toHaveBeenCalledWith({
      equipamento_id: 1,
      solicitante: 'Bia',
      inicio: '2026-11-20 12:00:00',
      fim: '2026-11-20 13:00:00'
    }, 'teste')
  })

  it('shows an accessible error when initial loading fails', async () => {
    loadCatalog.mockRejectedValueOnce(new Error('Falha HTTP 500'))
    render(<App />)
    expect(await screen.findByRole('alert')).toHaveTextContent('Falha HTTP 500')
  })

  it('shows an accessible error when reservation creation fails', async () => {
    createReservation.mockRejectedValueOnce(new Error('Período indisponível'))
    const user = userEvent.setup()
    render(<App />)
    await screen.findByRole('heading', { name: 'Projetor A' })
    await user.selectOptions(screen.getByLabelText('Equipamento'), '1')
    await user.type(screen.getByLabelText('Solicitante'), 'Bia')
    await user.type(screen.getByLabelText('Início'), '2026-11-20T12:00')
    await user.type(screen.getByLabelText('Fim'), '2026-11-20T13:00')
    await user.type(screen.getByLabelText('Chave da API'), 'teste')
    await user.click(screen.getByRole('button', { name: 'Criar reserva' }))
    expect(await screen.findByRole('alert')).toHaveTextContent('Período indisponível')
  })
})
