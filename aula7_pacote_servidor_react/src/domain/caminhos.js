export function resolverAsset(caminho, base = document.baseURI) {
  return new URL(caminho, base).href;
}
