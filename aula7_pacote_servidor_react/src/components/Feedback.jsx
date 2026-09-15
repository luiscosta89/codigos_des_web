export default function Feedback({ mensagem, onFechar }) {
  if (!mensagem) return null;
  return (
    <div className="toast-container position-fixed bottom-0 end-0 p-3">
      <div className="toast show" role="status" aria-live="polite">
        <div className="toast-header">
          <strong className="me-auto">TechStore</strong>
          <button className="btn-close" type="button" aria-label="Fechar mensagem" onClick={onFechar} />
        </div>
        <div className="toast-body">{mensagem}</div>
      </div>
    </div>
  );
}
