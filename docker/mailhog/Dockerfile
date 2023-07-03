FROM golang:1.20

RUN go install github.com/mailhog/MailHog@latest
EXPOSE 1025 8025
ENTRYPOINT ["MailHog"]
