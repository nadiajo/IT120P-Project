import { Component, OnInit } from '@angular/core';
import { Title, Meta } from '@angular/platform-browser';
import { HttpClient } from '@angular/common/http';
import { DogService } from '../../services/dog.service';
import { Dog } from '../../models/dog';

@Component({
  selector: 'admin-home',
  templateUrl: 'admin-home.component.html',
  styleUrls: ['admin-home.component.css'],
})
export class AdminHome implements OnInit {
  rawvrfu: string = ' ';
  rawr2q0: string = ' ';
  rawf27t: string = ' ';
  rawnf8v: string = ' ';
  dogs: Dog[] = [];

  constructor(
    private title: Title,
    private meta: Meta,
    private http: HttpClient,
    private dogService: DogService
  ) {
    this.title.setTitle('Admin-Home - angular-design');
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'Admin-Home - angular-design',
      },
    ]);
  }

  ngOnInit(): void {
    this.dogService.getDogs().subscribe(
      (data) => {
        this.dogs = data;
      },
      (error) => {
        console.log('Error fetching dogs:', error);
      }
    );
  }

  deleteDog(id: number) {
    this.dogService.deleteDog(id).subscribe(
      () => {
        this.dogService.getDogs().subscribe(
          (data) => {
            this.dogs = data;
          },
          (error) => {
            console.log('Error fetching dogs:', error);
          }
        );
      },
      (error) => {
        console.log('Error deleting dog:', error);
      }
    );
  }
}