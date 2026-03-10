import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet],
  template: `
    <div class="app-container">
      <header class="app-header">
        <h1>🐔 Akoho</h1>
        <span class="subtitle">Application Full-Stack</span>
      </header>
      <main class="app-main">
        <router-outlet></router-outlet>
      </main>
      <footer class="app-footer">
        <p>Akoho © 2026 - Node.js + Angular + SQL Server</p>
      </footer>
    </div>
  `,
  styles: [`
    .app-container {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .app-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 20px 40px;
      display: flex;
      align-items: center;
      gap: 20px;
      box-shadow: var(--shadow);
    }

    .app-header h1 {
      font-size: 1.8rem;
      font-weight: 700;
    }

    .subtitle {
      font-size: 0.9rem;
      opacity: 0.9;
      padding: 4px 12px;
      background: rgba(255,255,255,0.2);
      border-radius: 20px;
    }

    .app-main {
      flex: 1;
      padding: 40px;
    }

    .app-footer {
      background: var(--bg-dark);
      color: white;
      padding: 15px;
      text-align: center;
      font-size: 0.85rem;
    }
  `]
})
export class AppComponent {
  title = 'Akoho';
}
