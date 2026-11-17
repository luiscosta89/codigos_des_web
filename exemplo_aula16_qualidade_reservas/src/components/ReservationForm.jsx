import { useState } from 'react'
import { reservationPayload } from '../lib/period.js'

export default function ReservationForm({ equipment, onSubmit, busy }) {
  const [form, setForm] = useState({ equipmentId: '', requester: '', startsAt: '', endsAt: '', apiKey: '' })
  const update = (key) => (event) => setForm({ ...form, [key]: event.target.value })
  const submit = async (event) => {
    event.preventDefault()
    await onSubmit(reservationPayload(form), form.apiKey)
  }
  return <section className="panel form-panel"><div><p className="eyebrow">Fluxo protegido</p><h2>Nova reserva</h2><p>A chave fica apenas na memória desta página e é enviada no cabeçalho do POST.</p></div>
    <form onSubmit={submit}>
      <label>Equipamento<select aria-label="Equipamento" required value={form.equipmentId} onChange={update('equipmentId')}><option value="">Selecione</option>{equipment.filter((item) => item.disponivel).map((item) => <option value={item.id} key={item.id}>{item.nome}</option>)}</select></label>
      <label>Solicitante<input required maxLength="120" value={form.requester} onChange={update('requester')} /></label>
      <label>Início<input type="datetime-local" required value={form.startsAt} onChange={update('startsAt')} /></label>
      <label>Fim<input type="datetime-local" required value={form.endsAt} onChange={update('endsAt')} /></label>
      <label>Chave da API<input type="password" required autoComplete="off" value={form.apiKey} onChange={update('apiKey')} /></label>
      <button disabled={busy}>{busy ? 'Enviando…' : 'Criar reserva'}</button>
    </form>
  </section>
}
