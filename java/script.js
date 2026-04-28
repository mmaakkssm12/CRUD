function Discriminant(a, b, c) {
    return b * b - 4 * a * c;
}

let a = 1; 
let b = 5; 
let c = 6; 

let D =Discriminant(a, b, c);

console.log("Дискриминант (D) равен:", D);