import { Component, OnInit } from '@angular/core';
import { Title, Meta } from '@angular/platform-browser';
import { ActivatedRoute } from '@angular/router';
import { DogService } from '../../services/dog.service';
import { Dog } from '../../models/dog';

@Component({
  selector: 'admin-update-dog',
  templateUrl: 'admin-update-dog.component.html',
  styleUrls: ['admin-update-dog.component.css'],
})
export class AdminUpdateDog implements OnInit {
  dog: Dog;
  originalDog: Dog;  // To store the original state

  constructor(
    private title: Title,
    private meta: Meta,
    private route: ActivatedRoute,
    private dogService: DogService
  ) {
    this.title.setTitle('Admin-Update-Dog - angular-design');
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'Admin-Update-Dog - angular-design',
      },
    ]);
  }

  ngOnInit(): void {
    const id = this.route.snapshot.params['id']; // Assuming the ID is a route parameter

    this.dogService.getDog(id).subscribe(
      (data) => {
        this.dog = data;
        this.originalDog = { ...data };  // Create a shallow copy of the original data
      },
      (error) => {
        console.log('Error fetching dog:', error);
      }
    );
  }

  updateDog(): void {
    this.dogService.updateDog(this.dog.id, this.dog).subscribe(
      (data) => {
        console.log('Dog updated successfully');
      },
      (error) => {
        console.log('Error updating dog:', error);
      }
    );
  }

  resetForm(): void {
    this.dog = { ...this.originalDog };  // Restore the dog object to its original state
  }
}
