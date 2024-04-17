import { Component } from '@angular/core'
import { Title, Meta } from '@angular/platform-browser'

@Component({
  selector: 'admin-profile',
  templateUrl: 'admin-profile.component.html',
  styleUrls: ['admin-profile.component.css'],
})
export class AdminProfile {
  constructor(private title: Title, private meta: Meta) {
    this.title.setTitle('Admin-Profile - angular-design')
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'Admin-Profile - angular-design',
      },
    ])
  }
}
