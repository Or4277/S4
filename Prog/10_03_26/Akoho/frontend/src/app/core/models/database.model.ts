/**
 * Interface représentant le statut de la base de données
 */
export interface DatabaseStatus {
  status: 'CONNECTED' | 'DISCONNECTED' | 'ERROR';
  message: string;
  database?: string;
  server?: string;
  timestamp: string;
}

/**
 * Interface représentant la réponse de connexion
 */
export interface ConnectionResponse {
  success: boolean;
  message: string;
  database?: string;
  server?: string;
  error?: string;
}

/**
 * Interface représentant la santé de l'API
 */
export interface HealthStatus {
  status: string;
  message: string;
  timestamp: string;
}
