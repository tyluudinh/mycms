# Observers of publishers

- eg: after placing an order successfully,
send notification via email, push notification, or write to logger

- consider to handle the update() method by adding the job to queue,
then the queue listener will call handler in Observer object again