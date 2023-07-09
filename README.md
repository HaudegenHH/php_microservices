
## Symphony 6 / Microservices 

"Introduction to Monolithic Architecture and MicroServices Architecture"

https://medium.com/koderlabs/introduction-to-monolithic-architecture-and-microservices-architecture-b211a5955c63

---

- in this project i use the symphony framework in order to build a microservice

- microservices are becoming more and more popular and they ve been made (more) possible by advances
  in technology and the availability of tools such as amazon web services (AWS) cloud computing tools 
  which enable you to have a network of loosely coupled services which are able to communicate 
  with each other

- the link above leads to an article that elaborates on the difference between microservices and 
  monolithics. The first diagram you ll see is showing an application based on a monolithic 
  architecture.

- that means that it is a single tiered application where everything is contained in a single repo, 
  hosted on a single platform. 

**The benefits of this approach are:**

- that it is a simple architecture
- its simple to develop
- simple to deploy and
- can easily scale horizontally by running multiple copies behind a load balancer

**There are however several downsides to this approach!**

- large monolithic code bases can be intimidating to developers and the application can be difficult 
  to understand and modify and so the code ends up throwing up as many problems as it actually solves
  and it becomes more difficult to change things and as a result the quality of the code declines
  over time
- continuous deployment is difficult because a large monolithic application is an obstacle to 
  frequent deployments
- scaling can be difficult also because you cant scale components independently. So you have that 
  interdependence which becomes a real problem in this respect and also that interdependence becomes
  a problem to development itself because it makes it more difficult to create teams which have 
  focussed responsibility for certain components, modules and work independently, you now have more 
  inter team dependencies which need to be coordinated 
- the probably biggest problem is that you can end up with a tech stack which you cannot update. You 
  might have an old version of PHP or an old version of a framework like Symphony which is no longer
  supported or maintained and thus you could be faced with the prospect of having to rewrite the 
  entire application, just in order to upgrade a framework (many companies fall into this trap!)

**A solution to all of that: microservices!**

- your application is composed of a set of loosely coupled collaborating services 
- in this type of architecture each service is easy to maintain and test
- can be worked on and deployed independently
- you can organize development around multiple autonomous teams, so your teams dont have to rely on 
  each other like they do with the big monolithic apps.
- you can choose your tech stack without being constrained by the rest of the application 
- you can upgrade your tech stack a lot more easier than what you can do with the monolith
- you can also isolate mistakes without them bringing down the entire application

**Microservices does have their drawbacks! Its not the perfect solution for everything..**
- you have to deal with additional complexity of a distributed system
- developers need to implement the inter-service communication and know how to deal with failures, so
  youll find that devs tend to need to understand a lot more than they did previously when monolithic
  architecture was more the norm in PHP 
- testing interaction between services can be difficult
- the increased deployment complexity can also pose problems

---

Now that ive explained what microservice are and how they might benefit us developers lets talk about, what im going to build and then finish of by installing the latest version of Symphony.

In this project im not going to be creating an entire networked set of microservices, im going to focus on one single microservice using the symphony framework. So ill be considering some of the components i can use in Symphony as well as thinking about the service and how they interact with the outside world. 

We're going to build a promotion engine which is a form of affiliate marketing tool.
If you look at the current PHP developer jobs market you ll see that there are lots of companies which are building these types of tool and they're building them as microservices, so i think it will be a good contemporary exercise.

The way it works is that the promotions engine has a bunch of affiliate marketing partners who are able to provide discounts on certain products, and then the client application or the service can fire a request into the promotions engine with various pieces of data. The engine will then do its thing, go and find the best value offerings based on the data it receives.
Some of the solutions you get are really smart, they can use like literally thousands of pieces of data and artificial intelligence in order to tailor offers towards the enquirer.
The one im creating isnt going to be quite that sophisticated but we ll still get into some interesting coding! 
I'll post a few pieces of data as json, then internally will apply some modifying and filtering on the available offerings which i ll have in the database, that i will create in order to return the one with the best price for that particular enquiry.


![Alt text](/assets/promotion.jpg?raw=true "Microservice Diagram")


If you look at the example diagram of the Promotions Engine, im just sending a few handful pieces of data (product_id, location, voucher_code, etc.), so you could look at this and say: the request is being made from a uk company and in that case we can give 20% off or whatever..
Whats actually happening here if you look at the response is its giving 50% off and its saying the reason why or the promotion name is the black friday half price sale because if you look at the request date: it is the 25th of Nov 2022, whcih just happens to be black friday and so that will be the best offering it can find within the engine.

---

### Create a new symphony project

If you dont already have the symphony cli globally installed

- open a terminal and run

```sh
iex "& {$(irm get.scoop.sh)} -RunAsAdmin"
```

- and after successful installation of scoop (cmd line installer)

```sh
scoop install symfony-cli
```

- you should be able to write now:

```sh
symfony new promotions-engine-microservice
```

- but you could of course stick to the composer to create a new symfony project

```sh
composer create-project symfony/skeleton:"6.3.*" project_name
```

---

Additional Libraries:

```sh
composer require --dev phpunit maker
```

---

https://www.toptal.com/developers/gitignore

Search for: 
- "Visual Studio Code" (,PHPStorm or whatever IDE you use)
- "Symfony"

- it ll create a gitignore for you which you add (not replace) to the existing .gitignore (eliminate duplicates)

---

## Controllers, Routes, etc

After installing the dependencies and setting up the symfony project, create a controller and the first route..

- create "ProductsController.php" in src/Controller
- git commit this 
- and switch to a new branch "routes", because in this one we're just going to set up the first 
  endpoint

### Routing

If you were creating lets say sth like a rest api, for example if you wanted to get all of the promotions you werent actually doing any kind of processing we were just hitting an endpoint and getting the data back and you 'd probably do sth like this..

-------

```sh
class ProductsController 
{
  #[Route('/products/{id}/promotions', name: 'promotions', methods: 'GET')]
  public function promotions() 
  {}  
}
```

--------

- using attributes or annotations to create the endpoint, returning all of the promotions for a product with a specific id
- also you define the name of that route and the methods which permissible for this endpoint
  (saying here: only get requests are permissible)

- so: if you were taking that kind of restful(!) approach where you were just hit an endpoint and get 
  some resources back, then this would be the thing to do 
- however thats not the approach that i'm taking. Its rather like: i want s.o. to hit an endpoint, do some processing (filtering/modifying in order to get the lowest price for a particular product), and for that youll do it little bit different...

```sh
#[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
public function lowestPrice(int $int): Response 
{} 
```

- unlike the restful approach, where in a POST request, a resource is created, here we will transfer JSON using POST to evaluate the best price and return it.
- notice that you can grab the route parameter (the id in this case) directly in the signature
- spin up the develpment server with

```sh
symfony serve -d
```

- like in docker "-d" stands for detached mode, meaning: the terminal is given back to us (server is 
  running in the background without blocking the terminal)

---

- typically you might decide you gonna start with the domain so you are going to start creating the 
  product entities or models, and then working on the actual logic of how the lowest price could be 
  evaluated
- that is probably the most common approach, but however: buildig a microservice you need to keep in
  your mind where you fit in in the outside world because there might be a wider application which 
  is dependent on the service, there might be other parts of development of the application which 
  might be dependent on this and thus its a good idea to give those other parts of the application, 
  those other services sth to play with, or at least sth which will return some data so that the 
  other teams could actually continue doing their development, e.g. the dev ops engineers could 
  start building out the network if they can actually hit the service and get some kind of response
  back
- so we jst put in a fake response, well, it wont be a faked response it be a real response with just 
  some fake output fields. You could find yourself in a situation where you are building sth like
  this, and you start with the internals (the algorithm for sorting, filtering, etc), BUT: you have
  other teams, which might ask when this will be ready and theres nothing worse when you re trying 
  to figure sth out, and being constantly badgered when sths going to be ready
- a nice compromise is just to give the other teams sth to work with from the start
- long story short: thats why we'll jst return a json response with a 200 status code, and as you can 
  see its the same data which gets posted in, and all you are gonna do is youre just going to add to 
  it or modify it if you need to..
- ..and for that reason we could use a **data transfer object**, where you get the fields posted in,
  deserialize it into an object, then do the filtering or modifying, and then serialize it again with 
  the newly added/modified fields and just send it back

```sh
#[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
public function lowestPrice(int $id): Response 
{
  return new JsonResponse([
    "quantity" => 5,
    "request_location" => "UK",
    "voucher_code" => "OU812",
    "request_date" => "2023-09-07",
    "product_id" => $id
  ], 200);
}  
```
- try it out in Postman (or e.g. in VSCode with Thunder Client)

- with that you have now a service which can be reached by other services and youre sending back a 
  response in the format which is similar if not the same as what you will actually get sent back
  when you have a finished product, but: its all quite "happy path"..
- sth you might want to consider is also given the option of sending back an example error response
- so that the other teams working on their little parts, can actually build in their own error 
  handling as well
- lets have a go at doing sth like that by adding a condition, so anyone working on other services
  which will communicate with this service can actually send a header to our service like a force 
  fail, just a particular key and if that key is present, then instead of sending back this 200 
  happy path success response, you send back an error response 

```sh
#[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
public function lowestPrice(int $id): Response 
{
  if($request->headers->has('force_fail')) {
    return new JsonResponse(
      ["error" => "Promotions Engine failure message"],
      $request->headers->get('force_fail')
    );
  }        

  return new JsonResponse([
    "quantity" => 5,
    "request_location" => "UK",
    "voucher_code" => "OU812",
    "request_date" => "2023-09-07",
    "product_id" => $id
  ], 200);
}  
```

