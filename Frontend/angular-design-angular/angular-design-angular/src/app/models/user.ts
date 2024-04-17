export enum Role {
    USER = 'USER',
    ADMIN = 'ADMIN'
  }
  
  export class User {
    constructor(
      public id: number,
      public username: string,
      public password: string,
      public role: Role
    ) {}
  }  