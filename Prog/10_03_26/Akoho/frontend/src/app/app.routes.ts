import { Routes } from '@angular/router';
import { ConnectionStatusComponent } from './features/connection-status/connection-status.component';

export const routes: Routes = [
  { path: '', redirectTo: '/status', pathMatch: 'full' },
  { path: 'status', component: ConnectionStatusComponent },
  { path: '**', redirectTo: '/status' }
];
