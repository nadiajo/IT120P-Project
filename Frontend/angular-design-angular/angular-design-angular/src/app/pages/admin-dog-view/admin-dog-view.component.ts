import { Component, OnInit } from '@angular/core';
import { Title, Meta } from '@angular/platform-browser';
import { ActivatedRoute, Router } from '@angular/router';
import { DogService } from '../../services/dog.service';
import { Dog } from '../../models/dog';

@Component({
  selector: 'admin-dog-view',
  templateUrl: 'admin-dog-view.component.html',
  styleUrls: ['admin-dog-view.component.css'],
})
export class AdminDogView implements OnInit {
  dog: Dog | null = null;

  constructor(
    private title: Title,
    private meta: Meta,
    private route: ActivatedRoute,
    private router: Router,
    private dogService: DogService
  ) {
    this.title.setTitle('Admin Dog View');
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'Admin-Dog-View - angular-design',
      },
    ]);
  }

  ngOnInit(): void {
    const id = +this.route.snapshot.paramMap.get('id');
    this.dogService.getDog(id).subscribe(
      (data) => {
        this.dog = data;
      },
      (error) => {
        console.log('Error fetching dog:', error);
      }
    );
  }

  deleteDog(): void {
    if (this.dog) {
      this.dogService.deleteDog(this.dog.id).subscribe(
        () => {
          this.router.navigate(['/admin-home']);
        },
        (error) => {
          console.log('Error deleting dog:', error);
        }
      );
    }
  }
}