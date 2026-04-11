const storageBase = import.meta.env.VITE_STORAGE_URL || 'http://localhost:8000'

/**
 * Resolve a storage path to a full URL.
 * Handles: /storage/... → http://localhost:8000/storage/...
 *          http://...    → unchanged
 *          null/''       → ''
 */
export function storageUrl(path) {
  if (!path) return ''
  if (path.startsWith('http://') || path.startsWith('https://')) return path
  if (path.startsWith('/storage/')) return storageBase + path
  return path
}
