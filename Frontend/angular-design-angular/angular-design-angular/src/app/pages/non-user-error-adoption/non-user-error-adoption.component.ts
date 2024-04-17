import { Component } from '@angular/core'
import { Title, Meta } from '@angular/platform-browser'

@Component({
  selector: 'non-user-error-adoption',
  templateUrl: 'non-user-error-adoption.component.html',
  styleUrls: ['non-user-error-adoption.component.css'],
})
export class NonUserErrorAdoption {
  constructor(private title: Title, private meta: Meta) {
    this.title.setTitle('Non-User-Error-Adoption - angular-design')
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'Non-User-Error-Adoption - angular-design',
      },
    ])
  }
}
