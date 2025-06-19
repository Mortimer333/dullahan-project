FROM nginx:stable
ADD ./dullahan.localhost.ext /etc/ssl/dullahan.localhost.ext
RUN apt-get update
RUN apt-get install -y openssl
RUN NAME=dullahan.localhost \
    && openssl req -x509 -newkey rsa:4096 -sha256 -days 3650 \
      -nodes -keyout /etc/ssl/$NAME.key -out /etc/ssl/$NAME.crt -subj "/CN=$NAME" \
      -addext "subjectAltName=DNS:$NAME,DNS:*.$NAME,IP:10.0.0.1"
#RUN openssl genrsa -out /etc/ssl/myCA.key 2048 \
#&& openssl req -x509 -new -nodes -key /etc/ssl/myCA.key -sha256 -days 3650 -out /etc/ssl/myCA.pem \
#        -subj "/C=PT/ST=Lisbon/L=Lisbon/O=MyCompany/OU=IT Department/CN=dullahan.localhost" \
#&& openssl genrsa -out /etc/ssl/dullahan.localhost.key 2048 \
#&& openssl req -new -key /etc/ssl/dullahan.localhost.key -out /etc/ssl/dullahan.localhost.csr \
#        -subj "/C=PT/ST=Lisbon/L=Lisbon/O=MyCompany/OU=IT Department/CN=dullahan.localhost" \
#RUN openssl x509 -req -in /etc/ssl/dullahan.localhost.csr -CA /etc/ssl/myCA.pem -CAkey /etc/ssl/myCA.key -CAcreateserial \
#            -out /etc/ssl/dullahan.localhost.crt -days 3650 -sha256 -extfile /etc/ssl/dullahan.localhost.ext
