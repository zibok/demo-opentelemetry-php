import React from "react";

export default function PlaylistBoard(props) {
    if (props.currentUser.id == 0) {
        return (
            <div>{"Please, select a user"}</div>
        )
    }

    return (
        <div>{"Playlist of user " + props.currentUser.name}</div>
    )
}
