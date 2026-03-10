import { Component, OnInit, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Subject, takeUntil, interval, startWith, switchMap } from 'rxjs';
import { HealthService } from '../../core/services/health.service';
import { DatabaseStatus, HealthStatus } from '../../core/models/database.model';

@Component({
  selector: 'app-connection-status',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './connection-status.component.html',
  styleUrls: ['./connection-status.component.css']
})
export class ConnectionStatusComponent implements OnInit, OnDestroy {
  // État du composant
  apiStatus: HealthStatus | null = null;
  databaseStatus: DatabaseStatus | null = null;
  isLoading = false;
  isConnecting = false;
  errorMessage: string | null = null;

  // Gestion de la destruction
  private destroy$ = new Subject<void>();

  constructor(private healthService: HealthService) {}

  ngOnInit(): void {
    this.checkStatus();
    
    // Vérification automatique toutes les 10 secondes
    interval(10000)
      .pipe(
        takeUntil(this.destroy$),
        startWith(0),
        switchMap(() => this.healthService.checkDatabaseStatus())
      )
      .subscribe({
        next: (status) => {
          this.databaseStatus = status;
          this.errorMessage = null;
        },
        error: (err) => {
          // Ne pas écraser le message si c'est juste un polling qui échoue
        }
      });
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }

  /**
   * Vérifie le statut de l'API et de la base de données
   */
  checkStatus(): void {
    this.isLoading = true;
    this.errorMessage = null;

    // Vérifier l'API
    this.healthService.checkApiHealth()
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: (status) => {
          this.apiStatus = status;
        },
        error: (err) => {
          this.apiStatus = null;
          this.errorMessage = err.message;
          this.isLoading = false;
        }
      });

    // Vérifier la base de données
    this.healthService.checkDatabaseStatus()
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: (status) => {
          this.databaseStatus = status;
          this.isLoading = false;
        },
        error: (err) => {
          this.databaseStatus = null;
          this.errorMessage = err.message;
          this.isLoading = false;
        }
      });
  }

  /**
   * Tente de connecter à la base de données
   */
  connect(): void {
    this.isConnecting = true;
    this.errorMessage = null;

    this.healthService.connectDatabase()
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: (response) => {
          this.isConnecting = false;
          if (response.success) {
            this.checkStatus();
          } else {
            this.errorMessage = response.message;
          }
        },
        error: (err) => {
          this.isConnecting = false;
          this.errorMessage = err.message;
        }
      });
  }

  /**
   * Déconnecte de la base de données
   */
  disconnect(): void {
    this.isConnecting = true;
    this.errorMessage = null;

    this.healthService.disconnectDatabase()
      .pipe(takeUntil(this.destroy$))
      .subscribe({
        next: (response) => {
          this.isConnecting = false;
          this.checkStatus();
        },
        error: (err) => {
          this.isConnecting = false;
          this.errorMessage = err.message;
        }
      });
  }

  /**
   * Vérifie si la base de données est connectée
   */
  get isConnected(): boolean {
    return this.databaseStatus?.status === 'CONNECTED';
  }

  /**
   * Retourne la classe CSS selon le statut
   */
  getStatusClass(): string {
    if (!this.databaseStatus) return 'status-unknown';
    switch (this.databaseStatus.status) {
      case 'CONNECTED': return 'status-connected';
      case 'DISCONNECTED': return 'status-disconnected';
      case 'ERROR': return 'status-error';
      default: return 'status-unknown';
    }
  }
}
