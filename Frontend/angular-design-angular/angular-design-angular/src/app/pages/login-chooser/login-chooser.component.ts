import { Component } from '@angular/core'
import { Title, Meta } from '@angular/platform-browser'

@Component({
  selector: 'login-chooser',
  templateUrl: 'login-chooser.component.html',
  styleUrls: ['login-chooser.component.css'],
})
export class LoginChooser {
  constructor(private title: Title, private meta: Meta) {
    this.title.setTitle('LoginChooser - angular-design')
    this.meta.addTags([
      {
        property: 'og:title',
        content: 'LoginChooser - angular-design',
      },
    ])
  }
}
