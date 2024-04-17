import { Component } from '@angular/core'
import { Title, Meta } from '@angular/platform-browser'

@Component({
  selector: 'dog-profile',
  templateUrl: 'dog-profile.component.html',
  styleUrls: ['dog-profile.component.css'],
})
export class DogProfile {
  constructor(private title: Title, private meta: Meta) {
    this.title.setTitle('Dog-Profile - angular-design')
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'Dog-Profile - angular-design',
      },
    ])
  }
}
