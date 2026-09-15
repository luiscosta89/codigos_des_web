const [favorito, setFavorito] = useState(false);

<button
  className={favorito ? 'btn btn-danger' : 'btn btn-outline-danger'}
  onClick={() => setFavorito(!favorito)}
></button>

