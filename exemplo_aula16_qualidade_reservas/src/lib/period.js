export function formatPeriod(start, end) {
  const format = (value) => new Intl.DateTimeFormat('pt-BR', {
    dateStyle: 'short', timeStyle: 'short'
  }).format(new Date(value.replace(' ', 'T')))
  return `${format(start)} — ${format(end)}`
}

export function reservationPayload({ equipmentId, requester, startsAt, endsAt }) {
  const sqlDate = (value) => `${value.replace('T', ' ')}:00`
  return {
    equipamento_id: Number(equipmentId),
    solicitante: requester.trim(),
    inicio: sqlDate(startsAt),
    fim: sqlDate(endsAt)
  }
}
