import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { User } from '../models/user';  // Adjust the path as needed

@Injectable({
  providedIn: 'root',
})
export class LoginService {
  
  constructor(private http: HttpClient) {}

  login(user: User) {
    return this.http.post('/api/login', {
      username: user.username,
      password: user.password
    });
  }

  // Additional methods for logout, current user details, etc.
}
