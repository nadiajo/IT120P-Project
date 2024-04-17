import { Component } from '@angular/core'
import { Title, Meta } from '@angular/platform-browser'

@Component({
  selector: 'user-home',
  templateUrl: 'user-home.component.html',
  styleUrls: ['user-home.component.css'],
})
export class UserHome {
  rawjkw1: string = ' '
  rawbzih: string = ' '
  constructor(private title: Title, private meta: Meta) {
    this.title.setTitle('User-Home - angular-design')
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'User-Home - angular-design',
      },
    ])
  }
}
