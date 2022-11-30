let countDownDate = new Date("<?= date('M j, Y H:i:s', strtotime($settings['event_starting_date'])); ?>").getTime();
                    let countDownDateEnd = new Date("<?= date('M j, Y H:i:s', strtotime($settings['event_ending_date'])); ?>").getTime();

                    const update_counter = () => {
                        let now = new Date().getTime();
                        let distance = countDownDate - now;
                        let distanceEnd = countDownDateEnd - now;

                        if (distanceEnd < 0) {
                            clearInterval(interval);
                            document.getElementById("counter_status").innerHTML = "L'évènement est terminé. <br> Merci pour votre participation !";
                            document.getElementById("counters_container").style.display = "none";
                            return;
                        }

                        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        if (distance < 0) {
                            document.getElementById("days_counter_container").style.display = "none";
                            document.getElementById("counter_status").innerHTML = "L'évènement a démarré depuis :";
                            document.getElementById("hours_counter").innerHTML = hours > -10 ? "0" + hours.toString().split("-").pop() : hours.toString().split("-").pop();
                            document.getElementById("minutes_counter").innerHTML = minutes > -10 ? "0" + minutes.toString().split("-").pop() : minutes.toString().split("-").pop();
                            document.getElementById("seconds_counter").innerHTML = seconds > -10 ? "0" + seconds.toString().split("-").pop() : seconds.toString().split("-").pop();
                        } else {
                            if (days <= 0) {
                                document.getElementById("days_counter_container").style.display = "none";
                            } else {
                                document.getElementById("days_counter").innerHTML = days.toString();
                            }
                            document.getElementById("counter_status").innerHTML = "Début de l'évènement dans :";
                            document.getElementById("hours_counter").innerHTML = hours < 10 ? "0" + hours.toString().split("-").pop() : hours.toString();
                            document.getElementById("minutes_counter").innerHTML = minutes < 10 ? "0" + minutes.toString().split("-").pop() : minutes.toString();
                            document.getElementById("seconds_counter").innerHTML = seconds < 10 ? "0" + seconds.toString().split("-").pop() : seconds.toString();
                        }
                    }

                    const interval = setInterval(update_counter, 1000);

                    update_counter();