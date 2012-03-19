# Configuration Options
This page contains options that can be set or used within prggmr.

Configuration options must be set before loading prggmr.

Changing any configuration can drastically reduce performance, make sure you
know what you are doing.

## Engine

### BINARY_ENGINE_SEARCH
__Default :__ 75

Number of storage nodes required before using binary searching, changing this to 
a lower value will decrease the seek time when firing signals but will increase 
the storage time.

For low count signal systems this is recommended to leave at a higher value 
since the time required to sort the storage will outweigh the benefit of binary
searching.

## Queue

### QUEUE_MAX_SIZE
__Default :__ 24

Maximum number of handles that can be assigned to a signal.

### QUEUE_DEFAULT_PRIORITY
__Default :__ 100

Default priority for a handle entered into the queue.

## QUEUE_MIN_HEAP
Instruct the queue to perform as a MIN heap where 0 comes first.

## QUEUE_MAX_HEAP
Instruct the queue to perform as a MAX heap where 0 comes last.