# Alura's Object Calisthenics

Examples from Alura's Object Calisthenics training.

## Rules

### Don't use _getters_ and _setters_:

---
Instead:
```
<?php

class Video
{
    private bool $visible;

    public function getVisible(): bool
    {
        return $this->status;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }
}
```
use expressive methods:
```
<?php

class Video
{
    private bool $visible;

    public function isPublic(): bool
    {
        return $this->$visible;
    }

    public function publish(): void
    {
        $this->$visible = true;
    }
}
```

### Have only one level of indentation per method

---
Instead of having nested structures like below: 
```
<?php

use DateTimeInterface;

class User
{
    private bool $isConfirmed; 
    private DateTimeInterface $lastAcess;
    
    public function hasAccess(): bool
    {
        if ($this->isConfirmed) {
            $today = new DateTimeImmutable();

            if ($lastAcess->diff($today)->days >= 90) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
```

refactor the code using private methods, early returns, fail first, etc.
```
<?php

use DateTimeInterface;

class User
{
    private bool $isActive; 
    private DateTimeInterface $lastAcess;
    
    public function hasAccess(): bool
    {
        if ($this->isConfirmed) {
            return false;
        }
        
        $today = new DateTimeImmutable();

        return $this->lastAcess->diff($today)->days < 90
    }
}
```

### Don't use _else_:

---
The previous rule also exemplifies this rule.

### Wrap primitive types when they have rules

---
In this case, a user has an email, which needs to be validated, which is not convenient for the user's domain:
```
<?php

class User
{
    public function __construct(private string $email, private string $name) 
    {
        $this->setEmail($email);
    }
    
    private function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid e-mail address');
        }

        $this->email = $email;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
}
```

it is more convenient to wrap the `email` attribute in a specific class with its own rules:
```
<?php

class Email
{
    public function __construct(private string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid e-mail address');
        }
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
```

so the user class doesn't have to worry about email responsibilities:
```
<?php

class User
{
    public function __construct(private Email $email, private string $name) 
    {
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
}
```

### First class collection

---

A class that contains a collection must only have methods associated with this structure. So instead of:

```
<?php

use SplObjectStore;

class User
{
    private SplObjectStore $emails;
    
    public function __construct(private int $id, private string $name) 
    {
        $this->emails = new SplObjectStore();
    }
    
    public function userInfo(): string
    {
        return "User: {$this->name}, identified by: {$this->id}.";
    }
    
    public function addEmail(Email $email): void
    {
        $this->emails->attach($email);
    }
    
    public function removeEmail(Email $email): void
    {
        $this->emails->dettach($email);
    }
    
    public function countEmails(): int
    {
        return $this->emails->count();
    }
}
```

do it:
```
<?php

use SplObjectStore;

class Emails
{
    private SplObjectStore $emails;
    
    public function __construct() 
    {
        $this->emails = new SplObjectStore();
    }
    
    public function addEmail(Email $email): void
    {
        $this->emails->attach($email);
    }
    
    public function removeEmail(Email $email): void
    {
        $this->emails->dettach($email);
    }
    
    public function countEmails(): int
    {
        return $this->emails->count();
    }
}
```
and:
```
<?php

class User
{
    private Emails $emails;
    
    public function __construct(private int $id, private string $name) 
    {
        $this->emails = new Emails();
    }
    
    public function userInfo(): string
    {
        return "User: {$this->name}, identified by: {$this->id}.";
    }
}
```

### Only one 'dot' (-> operator) per line

---

When needed access some information from another object to execute some action, this violates encapsulation and makes 
one entity know too much about another entity. Furthermore, this violates the 'only one dot per line' rule.

In this example, the class needs to calculate the age of the user to perform an action, for this the 'birthdate'
attribute of the user is accessed. See:

``` 
<?php

class User
{
    public function __construct(private DateTimeInterface $birthDate) 
    {
    }
    
    public function birthdate(): DateTimeInterface
    {
        return $this->birthdate;
    }
}
``` 
``` 
class SomeAction
{
    public function execute(User $user): void
    {
        $today = new \DateTimeImmutable();
        $age = $user->birthdate()->diff($today)->y;
        
        if ($age < 18) {
            return;
        }
        
        // do something...
    }
}
```

In `$user->birthdate()->diff($today)->y` we have three 'dot levels', but we can do this:

``` 
<?php

class User
{
    public function __construct(private DateTimeInterface $birthDate) 
    {
    }
    
    public function age(): int
    {
        $today = new \DateTimeImmutable();
        
        return $this->birthdate->diff($today)->y;
    }
}
``` 
``` 
class SomeAction
{
    public function execute(User $user): void
    {
        if ($user->age < 18) {
            return;
        }
        
        // do something...
    }
}
```

Now we have only one 'dot' and eliminate a generic _getter_, getting rich the user domain and encapsulating it.
Now we just have a 'dot' and we've dropped the generic `birthdate()` _getter_, enriching the user's domain and 
encapsulating it.

### NEVER using abbreviations

---
Instead:
```
<?php

class User
{
    public function __construct(
        private string $fName,
        private string $lName,
        private DateTimeInterface $bd
    ) 
    {
    }
}
```
do it:
```
<?php

class User
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private DateTimeInterface $birthDate
    ) 
    {
    }
}
```


### Keep classes and packages small

---
It's hard to exemplify this rule - and follow it to the letter - but it's good to try to keep classes to a maximum of 50 
lines and packages (namespaces) with a maximum of 10 classes.


### Have a maximum of two attributes per class

---
Another very hard rule to follow to the letter, but it's good to have a minimum of attributes in an entity to keep the
conciseness.