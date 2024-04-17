import { Component } from '@angular/core'
import { Title, Meta } from '@angular/platform-browser'

@Component({
  selector: 'app-homepage',
  templateUrl: 'homepage.component.html',
  styleUrls: ['homepage.component.css'],
})
export class Homepage {
  rawye79: string = ' '
  rawxa7i: string = ' '
  constructor(private title: Title, private meta: Meta) {
    this.title.setTitle('angular-design')
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'angular-design',
      },
    ])
  }
}
