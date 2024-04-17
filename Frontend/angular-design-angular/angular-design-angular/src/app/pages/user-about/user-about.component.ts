import { Component } from '@angular/core'
import { Title, Meta } from '@angular/platform-browser'

@Component({
  selector: 'user-about',
  templateUrl: 'user-about.component.html',
  styleUrls: ['user-about.component.css'],
})
export class UserAbout {
  rawunvr: string = ' '
  raw4vqz: string = ' '
  rawrda0: string = ' '
  rawwo9r: string = ' '
  constructor(private title: Title, private meta: Meta) {
    this.title.setTitle('User-About - angular-design')
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'User-About - angular-design',
      },
    ])
  }
}
