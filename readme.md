## Queue

Simple filesysetm based queue for PHP applications.

## Usage

**Client**

Put jobs to queue;

```php
$dir = dirname(__FILE__);
$queue = new Queue($dir . 'jobs');
$queue->put('send.mail', [
    'address' => 'example@example.com',
    'body' => 'Email body'
]);
```
**Worker**

```php
$dir = dirname(__FILE__);
$queue = new Queue($dir . 'jobs');
while($job = $queue->pull()){
    # do something with jobs
    var_dump($job->name); # string: job name
    var_dump($job->file); # string: path of job file
    var_dump($job->args); # array: args for jobs
    var_dump($job->created_at); # int: unix timestamp
}
```