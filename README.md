
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



