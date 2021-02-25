# How to contribute

We are really glad you're reading this.

## Testing

We have a handful of PHPUnit tests. Please write PHPUnit tests for new code you create.

## Submitting changes

Please send a [GitHub Pull Request to](https://github.com/Bakome/laravel-validation-attributes/pull/new/master) with a clear list of what you've done (read more about [pull requests](http://help.github.com/pull-requests/)). When you send a pull request, we will love you forever if you include PHPUnit tests. We can always use more test coverage. Please follow our coding conventions (below) and make sure all of your commits are atomic (one feature per commit).

Always write a clear log message for your commits. One-line messages are fine for small changes, but bigger changes should look like this:

    $ git commit -m "[Validation (Component)] A brief summary of the commit
    > 
    > A paragraph describing what changed and its impact."

## Coding conventions

Start reading our code and you'll get the hang of it. We optimize for readability:

  * We indent using four spaces (tabs)
  * We ALWAYS put spaces after list items and method parameters (`[1, 2, 3]`, not `[1,2,3]`).
  * This is open source software. Consider the people who will read your code, and make it look nice for them.
  * Refactor often.
  * Program for maintainer.
  * Respect project structure.
  * Avoid more then 3 parameters in methods (except in constructors).

Thanks
