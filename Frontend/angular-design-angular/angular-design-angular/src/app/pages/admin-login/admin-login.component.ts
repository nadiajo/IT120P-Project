import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { Title, Meta } from '@angular/platform-browser';

@Component({
  selector: 'admin-login',
  templateUrl: 'admin-login.component.html',
  styleUrls: ['admin-login.component.css'],
})
export class AdminLogin {
  loginFailed = false;
  username: string;
  password: string;

  constructor(
    private title: Title,
    private meta: Meta,
    private router: Router  // Import Router
  ) {
    this.title.setTitle('Admin-Login - angular-design');
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'Admin-Login - angular-design',
      },
    ]);
  }

  login(): void {
    if (this.username === 'admin' && this.password === 'password') {
      this.router.navigate(['/admin-home']);
      this.loginFailed = false;
    } else {
      this.loginFailed = true;
    }
  }
}