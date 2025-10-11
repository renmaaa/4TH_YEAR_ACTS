// A. Make an Array containing 5 javascripts object
let users = [
  { name: "Thelma", age: 47 },
  { name: "Reithel", age: 27 },
  { name: "Renma", age: 23 },
  { name: "Renielle", age: 16 },
  { name: "Deonne", age: 11 }
];

//B. forEach method
users.forEach(user => console.log(user));

//C. push method
users.push({ name: "Renren", age: 1 });
console.log(users);

//D. unshift method
users.unshift({ name: "Christan", age: 10 });
console.log(users);

//E. filter method
let filtered = users.filter(user => user.age < 25);
console.log(filtered);

//F. map method
let names = users.map(user => user.name);
console.log(names);

//G. reduce method
let totalAge = users.reduce((sum, user) => sum + user.age, 0);
console.log(totalAge);

//H. some method
let hasMinor = users.some(user => user.age < 19);
console.log(hasMinor);

//I. every method
let allAdults = users.every(user => user.age >= 18);
console.log(allAdults);