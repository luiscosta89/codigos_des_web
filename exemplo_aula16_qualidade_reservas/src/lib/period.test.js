import { describe, expect, it } from 'vitest'
import { formatPeriod, reservationPayload } from './period.js'

describe('period helpers', () => {
  it('formats a reservation period for the interface', () => {
    expect(formatPeriod('2026-11-20 09:00:00', '2026-11-20 11:00:00')).toContain('20/11/2026')
  })

  it('creates the API payload with numeric equipment id', () => {
    expect(reservationPayload({ equipmentId: '2', requester: 'Ana', startsAt: '2026-11-20T09:00', endsAt: '2026-11-20T11:00' }))
      .toEqual({ equipamento_id: 2, solicitante: 'Ana', inicio: '2026-11-20 09:00:00', fim: '2026-11-20 11:00:00' })
  })
})
