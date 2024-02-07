FROM golang:1.22

RUN go install github.com/mailhog/MailHog@latest
EXPOSE 1025 8025
ENTRYPOINT ["MailHog"]
