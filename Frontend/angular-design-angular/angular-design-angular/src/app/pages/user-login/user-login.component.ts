import { Component } from '@angular/core';
import { Title, Meta } from '@angular/platform-browser';
import { Router } from '@angular/router';

@Component({
  selector: 'user-login',
  templateUrl: 'user-login.component.html',
  styleUrls: ['user-login.component.css'],
})
export class UserLogin {
  loginFailed = false;
  username: string;
  password: string;

  constructor(private title: Title, private meta: Meta, private router: Router) {
    this.title.setTitle('User-Login - angular-design');
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'User-Login - angular-design',
      },
    ]);
  }

  login(): void {
    if (this.username === 'user' && this.password === 'password') {
      this.router.navigate(['/user-home']);
      this.loginFailed = false;
    } else {
      this.loginFailed = true;
    }
  }
}