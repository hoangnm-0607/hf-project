FROM elasticsearch:7.12.0

ENV cluster.name docker-cluster
ENV bootstrap.memory_lock true
ENV ES_JAVA_OPTS -Xms512m -Xmx512m
ENV discovery.zen.ping.unicast.hosts elasticsearch
ENV discovery.type single-node
ENV xpack.security.enabled false