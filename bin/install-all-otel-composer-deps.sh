#!/bin/bash

for dir in lesfilmsquejekiffe favlist-svc catalog-svc user-svc ; do
    (
        cd $dir
        composer require \
            "open-telemetry/opentelemetry-auto-symfony ^1.0@beta" \
            open-telemetry/opentelemetry-auto-pdo \
            open-telemetry/sdk \
            open-telemetry/exporter-otlp 
    )
done
