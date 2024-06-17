import { AppBar, Box, FormControl, InputLabel, MenuItem, Select, Toolbar, Typography } from "@mui/material";
import React from "react";

export default function (props) {
    const handleChange= (event) => {
        alert(`Selected User: ${event.target.value}`);
    }
    return <Box sx={{ flexGrow: 1 }}>
            <AppBar position="static">
            <Toolbar>
                <Typography variant="h1" component="div" sx={{ flexGrow: 1 }}>Mate Ma Music !</Typography>
                <InputLabel id="user-selector-label">Choose a user</InputLabel>

                <FormControl variant="filled" sx={{ m: 1, minWidth: 120 }}>
                <Select labelId="user-selector-label"
                        label="User"
                        onChange={handleChange}>
                    {props.users.map((item) =>
                        <MenuItem value={item.id}>{item.name}</MenuItem>
                    )}
                </Select>
                </FormControl>
            </Toolbar>
        </AppBar>
    </Box>
}