import { Component } from '@angular/core'
import { Title, Meta } from '@angular/platform-browser'

@Component({
  selector: 'user-profile',
  templateUrl: 'user-profile.component.html',
  styleUrls: ['user-profile.component.css'],
})
export class UserProfile {
  constructor(private title: Title, private meta: Meta) {
    this.title.setTitle('User-Profile - angular-design')
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'User-Profile - angular-design',
      },
    ])
  }
}
