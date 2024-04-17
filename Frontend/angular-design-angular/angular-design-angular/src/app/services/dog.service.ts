import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Dog } from '../models/dog';
import { catchError } from 'rxjs/operators';
import { throwError } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class DogService {
  
  private baseUrl: string = 'http://localhost:18080/api';

  constructor(private http: HttpClient) {}

  private handleError(error: HttpErrorResponse) {
    console.error('An error occurred:', error);
    return throwError('Something bad happened; please try again later.');
  }

  getDogs() {
    return this.http.get<Dog[]>(`${this.baseUrl}/dogs`).pipe(catchError(this.handleError));
  }

  getDog(id: number) {
    return this.http.get<Dog>(`${this.baseUrl}/show-dog/${id}`).pipe(catchError(this.handleError));
  }

  addDog(dog: Dog) {
    return this.http.post<Dog>(`${this.baseUrl}/add-dog`, dog).pipe(catchError(this.handleError));
  }

  updateDog(id: number, updatedDog: Dog) {
    return this.http.put<Dog>(`${this.baseUrl}/update-dog/${id}`, updatedDog).pipe(catchError(this.handleError));
  }

  deleteDog(id: number) {
    return this.http.delete(`${this.baseUrl}/delete-dog/${id}`).pipe(catchError(this.handleError));
  }  

  expressInterest(id: number) {
    return this.http.post(`${this.baseUrl}/interest/${id}`, {}, {responseType: 'text'}).pipe(catchError(this.handleError));
  }
}
