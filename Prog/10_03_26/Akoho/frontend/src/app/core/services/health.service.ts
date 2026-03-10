import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, catchError, throwError } from 'rxjs';
import { environment } from '../../../environments/environment';
import { DatabaseStatus, ConnectionResponse, HealthStatus } from '../models/database.model';

/**
 * Service pour gérer la communication avec l'API backend
 * concernant la santé et la connexion à la base de données
 */
@Injectable({
  providedIn: 'root'
})
export class HealthService {
  private readonly apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  /**
   * Vérifie la santé de l'API
   * @returns Observable<HealthStatus>
   */
  checkApiHealth(): Observable<HealthStatus> {
    return this.http.get<HealthStatus>(`${this.apiUrl}/health`)
      .pipe(catchError(this.handleError));
  }

  /**
   * Vérifie le statut de la connexion à la base de données
   * @returns Observable<DatabaseStatus>
   */
  checkDatabaseStatus(): Observable<DatabaseStatus> {
    return this.http.get<DatabaseStatus>(`${this.apiUrl}/health/database`)
      .pipe(catchError(this.handleError));
  }

  /**
   * Tente de se connecter à la base de données
   * @returns Observable<ConnectionResponse>
   */
  connectDatabase(): Observable<ConnectionResponse> {
    return this.http.post<ConnectionResponse>(`${this.apiUrl}/health/database/connect`, {})
      .pipe(catchError(this.handleError));
  }

  /**
   * Déconnecte de la base de données
   * @returns Observable<ConnectionResponse>
   */
  disconnectDatabase(): Observable<ConnectionResponse> {
    return this.http.post<ConnectionResponse>(`${this.apiUrl}/health/database/disconnect`, {})
      .pipe(catchError(this.handleError));
  }

  /**
   * Gestion des erreurs HTTP
   * @param error - L'erreur HTTP reçue
   * @returns Observable<never>
   */
  private handleError(error: HttpErrorResponse): Observable<never> {
    let errorMessage = 'Une erreur est survenue';

    if (error.error instanceof ErrorEvent) {
      // Erreur côté client
      errorMessage = `Erreur: ${error.error.message}`;
    } else if (error.status === 0) {
      // Serveur inaccessible
      errorMessage = 'Impossible de se connecter au serveur. Vérifiez que le backend est démarré.';
    } else {
      // Erreur côté serveur
      errorMessage = error.error?.message || `Erreur serveur: ${error.status}`;
    }

    console.error('Erreur HTTP:', errorMessage);
    return throwError(() => new Error(errorMessage));
  }
}
