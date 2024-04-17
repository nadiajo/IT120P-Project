import { Component } from '@angular/core';
import { Title, Meta } from '@angular/platform-browser';
import { DogService } from '../../services/dog.service';
import { Dog } from '../../models/dog';

@Component({
  selector: 'admin-add-dog',
  templateUrl: 'admin-add-dog.component.html',
  styleUrls: ['admin-add-dog.component.css'],
})
export class AdminAddDog {
  newDog: Dog = new Dog(0, '', '', 0, '', '');

  constructor(
    private title: Title,
    private meta: Meta,
    private dogService: DogService
  ) {
    this.title.setTitle('Admin-Add-Dog - angular-design');
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'Admin-Add-Dog - angular-design',
      },
    ]);
  }

  addDog() {
    this.dogService.addDog(this.newDog).subscribe(
      response => {
        console.log("Dog added successfully", response);
        this.newDog = new Dog(0, '', '', 0, '', '');
      },
      error => {
        console.log("Error occurred: ", error);
      }
    );
  }
}