import { cp, mkdir, rm } from 'node:fs/promises'

await rm('dist/api', { recursive: true, force: true })
await mkdir('dist/api', { recursive: true })
await cp('api', 'dist/api', { recursive: true, filter: (source) => !source.includes('/tests') })
