import { Component } from '@angular/core'
import { Title, Meta } from '@angular/platform-browser'

@Component({
  selector: 'app-about',
  templateUrl: 'about.component.html',
  styleUrls: ['about.component.css'],
})
export class About {
  rawpcse: string = ' '
  raw3g81: string = ' '
  raw49fv: string = ' '
  rawdx0z: string = ' '
  constructor(private title: Title, private meta: Meta) {
    this.title.setTitle('About - angular-design')
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'About - angular-design',
      },
    ])
  }
}
