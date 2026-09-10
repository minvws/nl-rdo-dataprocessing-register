# Scheduled tasks

All periodic work runs through the Laravel scheduler. There are no crontab entries, systemd timers or
external schedulers in this repository. The single entry point is:

```
php artisan schedule:run
```

The hosting environment must call that command every minute. Laravel decides from there which of the
tasks below is due. If that call stops, everything in this document silently stops with it.
The schedule itself is defined in `src/cms/app/Console/Kernel.php`.

## Checking and testing

```
php artisan schedule:list                  # what is registered and when it runs next
php artisan schedule:test --name <command> # run one task now, outside the schedule
```
