import { cleanup, render, screen } from '@testing-library/react'
import { afterEach, describe, expect, it } from 'vitest'
import { ReservationList } from './Lists.jsx'

afterEach(cleanup)

describe('ReservationList', () => {
  it('distinguishes pending, confirmed and canceled statuses visually', () => {
    render(<ReservationList reservations={[
      { id: 1, equipamento_nome: 'Projetor', solicitante: 'Ana', inicio: '2030-03-18 09:00:00', fim: '2030-03-18 10:00:00', status: 'pendente' },
      { id: 2, equipamento_nome: 'Câmera', solicitante: 'Bia', inicio: '2030-03-18 10:00:00', fim: '2030-03-18 11:00:00', status: 'confirmada' },
      { id: 3, equipamento_nome: 'Notebook', solicitante: 'Caio', inicio: '2030-03-18 11:00:00', fim: '2030-03-18 12:00:00', status: 'cancelada' }
    ]} />)
    expect(screen.getByText('pendente')).toHaveClass('status-pendente')
    expect(screen.getByText('confirmada')).toHaveClass('status-confirmada')
    expect(screen.getByText('cancelada')).toHaveClass('status-cancelada')
  })
})