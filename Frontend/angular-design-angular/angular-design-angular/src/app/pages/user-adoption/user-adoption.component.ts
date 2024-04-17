import { Component, OnInit } from '@angular/core';
import { Title, Meta } from '@angular/platform-browser';
import { DogService } from '../../services/dog.service';
import { Dog } from '../../models/dog';
import { catchError } from 'rxjs/operators';

@Component({
  selector: 'user-adoption',
  templateUrl: 'user-adoption.component.html',
  styleUrls: ['user-adoption.component.css'],
})
export class UserAdoption implements OnInit {
  dogs: Dog[] = [];

  constructor(
    private title: Title,
    private meta: Meta,
    private dogService: DogService  // Inject the DogService
  ) {
    this.title.setTitle('User-Adoption - angular-design');
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'User-Adoption - angular-design',
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

  onAdoptMeClicked(id: number) {
    this.dogService.expressInterest(id).subscribe(
      (response: any) => {
        try {
          let jsonResponse = JSON.parse(response);
          console.log('Interest expressed:', jsonResponse);
          // You can add further actions here, like updating the UI
        } catch (e) {
          console.log('Interest expressed:', response);
          // You can add further actions here, like updating the UI
        }
      },
      (error) => {
        console.log('Error:', error);
        // Handle the error here
      }
    );
  }
}