import { useCallback, useEffect, useState } from 'react'
import EquipmentList, { ReservationList } from './components/Lists.jsx'
import ReservationForm from './components/ReservationForm.jsx'
import { createReservation, loadCatalog } from './lib/api.js'
import './index.css'

export default function App() {
  const [data, setData] = useState({ equipamentos: [], reservas: [] })
  const [state, setState] = useState({ loading: true, busy: false, message: '', error: '' })
  const reload = useCallback(async () => {
    try { setData(await loadCatalog()); setState((value) => ({ ...value, loading: false, error: '' })) }
    catch (error) { setState((value) => ({ ...value, loading: false, error: error.message })) }
  }, [])
  useEffect(() => { reload() }, [reload])
  const submit = async (payload, key) => {
    setState((value) => ({ ...value, busy: true, message: '', error: '' }))
    try { await createReservation(payload, key); await reload(); setState((value) => ({ ...value, busy: false, message: 'Reserva criada com sucesso.' })) }
    catch (error) { setState((value) => ({ ...value, busy: false, error: error.message })) }
  }
  return <><header><p className="eyebrow">Aula 16 · TDD e qualidade</p><h1>ReservaLab</h1><p>Equipamentos acadêmicos, períodos explícitos e conflitos visíveis.</p></header><main>
    {state.loading && <p>Carregando…</p>}{state.error && <p className="notice error" role="alert">{state.error}</p>}{state.message && <p className="notice success" role="status">{state.message}</p>}
    <EquipmentList equipment={data.equipamentos} /><ReservationForm equipment={data.equipamentos} onSubmit={submit} busy={state.busy} /><ReservationList reservations={data.reservas} />
  </main><footer>GET público · POST com X-API-Key · PHP 8.2 + MariaDB 10.5</footer></>
}
