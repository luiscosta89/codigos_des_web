import { formatPeriod } from '../lib/period.js'

export default function EquipmentList({ equipment }) {
  return <section className="panel"><h2>Equipamentos</h2><div className="cards">
    {equipment.map((item) => <article className="card" key={item.id}>
      <span className={`badge ${item.disponivel ? 'ok' : ''}`}>{item.disponivel ? 'Disponível' : 'Indisponível'}</span>
      <h3>{item.nome}</h3><p>Patrimônio {item.patrimonio}</p>
    </article>)}
  </div></section>
}

export function ReservationList({ reservations }) {
  return <section className="panel"><h2>Reservas</h2>
    {reservations.length === 0 ? <p>Nenhuma reserva cadastrada.</p> : <div className="table-wrap"><table><thead><tr><th>Equipamento</th><th>Solicitante</th><th>Período</th><th>Status</th></tr></thead><tbody>
      {reservations.map((item) => <tr key={item.id}><td>{item.equipamento_nome}</td><td>{item.solicitante}</td><td>{formatPeriod(item.inicio, item.fim)}</td><td><span className={`badge status-${item.status}`}>{item.status}</span></td></tr>)}
    </tbody></table></div>}
  </section>
}
